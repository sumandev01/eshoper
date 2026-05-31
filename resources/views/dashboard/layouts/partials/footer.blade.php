<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
            {!! str_replace(
            ['{year}', '{site_title}', '&copy;'],
            [date('Y'), e($siteSettings?->site_title), '©'],
            $siteSettings?->footer_text,) !!}</span>
    </div>
</footer>
