<?php

namespace Modules\Admin\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class Google2FAController extends Controller
{
    const PAGE_TITLE = 'Google Two-Factor';

    /**
     * Enable Google Two-Factor Authentication
     *
     * @return View|RedirectResponse
     */
    public function enable()
    {
        try {
            $title = self::PAGE_TITLE;
            $google2FA = new Google2FA();
            $secretKey = $google2FA->generateSecretKey();

            $user = auth()->user();
            $user->update(['google2fa_secret' => $secretKey]);

            // Generate the QR Code URL
            $otpAuthUrl = $google2FA->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $secretKey
            );

            // Render the QR Code as SVG
            $renderer = new ImageRenderer(
                new RendererStyle(400),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCodeSvg = $writer->writeString($otpAuthUrl);

            return view('admin::admin.profile.2fa-setup', compact('secretKey', 'qrCodeSvg', 'title'));
        } catch (Exception $exception) {
            report($exception);

            return back()->with('danger', __('An error occurred: :message', ['message' => $exception->getMessage()]));
        }
    }

    /**
     * Disable Google Two-Factor Authentication
     *
     * @return RedirectResponse
     */
    public function disable()
    {
        try {
            $user = auth()->user();

            $user->update(['google2fa_secret' => null]);

            return to_route('admin.admin.profile.index')
                ->with('success', __('Two-factor authentication has been disabled successfully.'));
        } catch (Exception $exception) {
            report($exception);

            return back()->with('danger', __('An error occurred: :message', ['message' => $exception->getMessage()]));
        }
    }
}
