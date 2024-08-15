<div class="card" id="seo_container" style="display: none;">
    <div class="card-header">
        <h3 class="card-title">پروژه سئو</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse">
                <i class="fal fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="card-body">
        @php
            $seoKeywordsCount = null;
            $seoAgreementDuration = null;
            $seoAmountContent = null;
            if(isset($package)){
                $seoKeywordsCount = $package->seo_keywords_count;
                $seoAgreementDuration = $package->seo_agreement_duration;
                $seoAmountContent = $package->seo_amount_content;
            }
        @endphp
        <x-admin.input identify="seo_keywords_count" title="تعداد کلمات سئو شدنی" :old="$seoKeywordsCount" />
        <x-admin.input identify="seo_agreement_duration" title="مدت قرارداد" :old="$seoAgreementDuration"/>
        <x-admin.input identify="seo_amount_content" title="میزان تولید محتوا" :old="$seoAmountContent"/>
    </div>
</div>