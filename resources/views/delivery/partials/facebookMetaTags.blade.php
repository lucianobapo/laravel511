<meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}" />
<meta property="fb:admins" content="riodasostrasdelivery24hs"/>

<meta name="twitter:card" content="photo"/>
<meta name="twitter:url" content="{{ config('delivery.siteMetaTagCurrentUrl') }}"/>
<meta name="twitter:title" content="{{ trans('delivery.head.title') }}"/>
<meta name="twitter:description" content="{{ trans('delivery.head.metaDescription') }}"/>
<meta name="twitter:image" content="{{ config('delivery.siteImage') }}"/>

<meta property="og:url" content="{{ config('delivery.siteMetaTagCurrentUrl') }}"/>
<meta property="og:type" content="bar"/>
<meta property="og:title" content="{{ trans('delivery.head.title') }}"/>
<meta property="og:site_name" content="{{ trans('delivery.index.title') }}"/>
<meta property="og:image" content="{{ config('delivery.siteImage') }}"/>
<meta property="og:image:url" content="{{ config('delivery.siteImage') }}"/>
<meta property="og:image:secure_url" content="{{ config('delivery.siteSecureImage') }}"/>
<meta property="og:image:type" content="image/png"/>
<meta property="og:description" content="{{ trans('delivery.head.metaDescription') }}"/>
<meta property="og:updated_time" content="{{ Carbon\Carbon::now()->timestamp }}"/>