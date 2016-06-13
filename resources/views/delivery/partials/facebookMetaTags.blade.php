<meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}" />
<meta property="fb:admins" content="riodasostrasdelivery24hs"/>

<meta property="og:url" content="{{ config('delivery.siteCurrentUrl') }}"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="{{ trans('delivery.head.title') }}"/>
<meta property="og:site_name" content="{{ trans('delivery.index.title') }}"/>
<meta property="og:image:url" content="{{ config('delivery.siteImage') }}"/>
<meta property="og:image:secure_url" content="{{ config('delivery.siteSecureImage') }}"/>
<meta property="og:image:type" content="image/png"/>
<meta property="og:description" content="{{ trans('delivery.head.metaDescription') }}"/>
<meta property="og:updated_time" content="{{ Carbon\Carbon::now()->timestamp }}"/>