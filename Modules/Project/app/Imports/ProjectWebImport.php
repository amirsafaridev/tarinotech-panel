<?php

namespace Modules\Project\app\Imports;

use App\Helpers\Helper;
use App\Service\Json\WebProject\DomainTransformer;
use App\Service\Json\WebProject\HostTransformer;
use App\Service\Json\WebProject\LanguageTransformer;
use App\Service\Json\WebProject\SampleTransformer;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Enums\WebHostLocation;
use Modules\Project\app\Models\ProjectWeb;

class ProjectWebImport implements OnEachRow, SkipsEmptyRows, ToModel, WithHeadingRow, WithMultipleSheets, WithStartRow, WithValidation
{
    private function initialProjectData(array $row): array
    {
        $agreementAt = $row['agreement_at'];
        $deadlineAt = $row['deadline_at'];

        $data = [
            'title' => $row['title'],
            'domain' => $row['domain_primary'],
            'admin_id' => auth()->id(),
            'user_id' => $row['user_id'],
            'status_id' => $row['status_id'],
            'base_id' => ProjectBase::Web,
            'price' => $row['price'],
            'type_id' => $row['type_id'],
            'note' => $row['note'],
            'business_domain_id' => $row['business_domain_id'],
            'business_domain' => $row['business_domain'],
        ];

        if (! empty($agreementAt)) {
            $data['agreement_at'] = Helper::toGregorian($agreementAt);
        } else {
            $data['agreement_at'] = null;
        }

        if (! empty($deadlineAt)) {
            $data['deadline_at'] = Helper::toGregorian($deadlineAt);
        } else {
            $data['deadline_at'] = null;
        }

        return $data;
    }

    public function model(array $row): ProjectWeb
    {
        //$dob = $row[9] ? Helper::toGregorian($row[9]) : null;

        logger(str($row['host_location'])->trim());

        /* Set Domain */
        $domainPassword = $row['domain_password'] ? Crypt::encrypt($row['domain_password']) : '';
        $domains = resolve(DomainTransformer::class);
        $domains->setHaveDomain((bool) $row['have_domain']);
        $domains->setDomainProviderWebsite((string) $row['domain_provider_website']);
        $domains->setDomainUsername((string) $row['domain_username']);
        $domains->setDomainPassword($domainPassword);
        $domains->setDomainPrimary((string) $row['domain_primary']);
        $domains->setDomainsRequired(explode('|', $row['domains_required']));
        $domains->setOtherDomain((string) $row['other_domain']);

        /* Set Host */
        $hostPassword = $row['host_password'] ? Crypt::encrypt($row['host_password']) : '';
        $host = resolve(HostTransformer::class);
        $host->setHaveHost((bool) $row['have_host']);
        $host->setHostProvider((string) $row['host_provider']);
        $host->setHostUsername((string) $row['host_username']);
        $host->setHostPassword($hostPassword);
        $host->setHostLocation((string) $row['host_location'] ?? WebHostLocation::IR);
        $host->setHostMostVisit((bool) $row['host_most_visit']);

        /* Set Language */
        $language = resolve(LanguageTransformer::class);
        $language->setPrimaryLanguage((string) $row['primary_language']);
        $language->setLanguages(explode('|', $row['languages']));

        /* Set Sample */
        $favoriteSites = explode('|', $row['favorite_sites']);
        $similarSites = explode('|', $row['similar_sites']);
        $sample = resolve(SampleTransformer::class);
        $sample->setFavoriteSites($favoriteSites);
        $sample->setSimilarSites($similarSites);

        $insert = [
            'field_activity' => $row['field_activity'],
            'package_id' => $row['package_id'],
            'pages' => $row['pages'],
            'domains' => $domains->toArray(),
            'host' => $host->toArray(),
            'language' => $language->toArray(),
            'sample' => $sample->toArray(),
            'working_days' => $row['working_days'],
        ];

        $projectWeb = ProjectWeb::query()->create($insert);

        $projectParams = $this->initialProjectData($row);
        $projectWeb->project()->create($projectParams);

        return $projectWeb;
    }

    public function startRow(): int
    {
        return 3;
    }

    public function sheets(): array
    {
        return [0 => $this];
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'domain_primary' => 'required|max:255',

            'user_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:project_types,id',
            'status_id' => 'required|exists:project_statuses,id',

            'price' => 'required|integer',
            'deadline_at' => 'required',

            'field_activity' => 'required|max:255',
            'package_id' => 'required|exists:packages,id',
            'pages' => 'required|integer',
            'agreement_at' => 'required',
            'working_days' => 'required|integer',

            /* Business */
            'business_domain_id' => 'required|exists:business_domains,id',

            /* Domain */
            'domain_provider_website' => 'required_if:have_domain,on',
            'domain_username' => 'required_if:have_domain,on',
            'domain_password' => 'required_if:have_domain,on',

            /* Host */
            'host_provider' => 'required_if:have_host,on',
            'host_username' => 'required_if:have_host,on',
            'host_password' => 'required_if:have_host,on',
        ];
    }

    public function customValidationMessages(): array
    {
        return [

        ];
    }

    public function onRow(Row $row)
    {

    }
}
