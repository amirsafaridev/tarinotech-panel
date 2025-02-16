@php

    $isSigned = $model?->signable?->status === \Modules\Contract\app\Enums\SignableStatus::Signed;

    $signatureBase64 = '';

    if ($isSigned) {
        $signaturePath = 'private/sign/sign-real.png';

        if (Storage::exists($signaturePath)) {
            $signatureContent = Storage::get($signaturePath);
            $signatureBase64 = 'data:image/png;base64,' . base64_encode($signatureContent);
        } else {
            $signatureBase64 = 'File not found.';
        }
    }

@endphp
<table style="width: 100%">
    <tr>
        <td valign="top" width="50%" style="padding-right: 70px">
            <table>
                <tr valign="top">
                    <td>مهر و امضای نماینده کارفرما</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
        <td width="50%" style="padding-right: 70px">
            <table>
                <tr>
                    <td>مهر و امضای نماینده مجری</td>
                </tr>
                <tr>
                    <td>
                        @if($isSigned)
                            <img width="270" style="float: left;margin-top: -30px" src="{{ $signatureBase64 }}">
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <img width="100%" src="./sign/footer.jpg" alt="">
        </td>
    </tr>
</table>
