
<div class="border-t border-gray-300 mt-5">
    <x-subheading>Content Syntax</x-subheading>
    <div class="flex -mx-3">
        <div class="flex-auto w-1/2 px-3">
            <p class="my-2 text-sm text-gray-600">
                You can use a <code>@{{unsubscribe_link}}</code> tag to indicate where the unsubscribe link
                should be placed. <br>If not used, the link will be automatically added to the bottom of your email.
            </p>
        </div>
        <div class="flex-auto w-1/2 px-3">
            <p class="my-2 text-sm text-gray-600">
                If intending to use this send via the RSS system, you can use loop over and add RSS article details with the following syntax:
            </p>
            <pre class="text-sm text-gray-800 bg-gray-200 p-2 border border-gray-300"><code>@{{rss_loop}}
@{{rss_article_title}}
@{{rss_article_description}}
@{{rss_article_link}}
@{{rss_article_publish_date}}
@{{end_rss_loop}}</code></pre>
        </div>
    </div>
</div>
