@extends('layouts.template')

@section('title'){{$category->name}}@endsection
@section('description'){{$category->description}}@endsection

@section('seo')
    <meta property="og:locale" content="ru_RU" />
    <meta property="og:locale:alternate" content="uk_UA" />
    <meta property="og:type" content="object" />
    <meta property="og:title" content="{{$category->seo_title}}" />
    <meta property="og:description" content="{{$category->description}}" />
    <meta property="og:url" content="https://shkiv.com.ua/category/{{route('category',$category->parent->slug)}}/{{$category->slug}}" />
    <meta property="og:site_name" content="shkiv.com.ua" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="{{$category->description}}" />
    <meta name="twitter:title" content="{{$category->seo_title}}" />
@endsection


@section('breadcums')
    <nav class="container breadcrumb-wrapper" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">@lang('messages.home')</a></li>
            <li class="breadcrumb-item" aria-current="page">@lang('messages.catalog_header')</li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{route('category', $category->parent->slug)}}">{{$category->parent->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$category->name}}</a>
        </ol>
    </nav>
@endsection

@section('content')
<div class="subcat-title"><h1 class="text-center">{{$category->name}}</h1></div>

<section class="section-categories">

    <div class="section-categories__wrapper container">
        <div class="section-categories__item categories-item" data-spy="scroll" data-target="#categories-menu"
             data-offset="0">
            @if(!empty($category->image_catalog))
                <section class="section-banner container">
                    <img src="{{url($category->image_catalog)}}" alt="{{$category->title}}">
                </section>
            @endif

            <div class="categories-prodlist">
                @foreach($products as $product)
                    @if(empty($product->slug)) @continue @endif
                    <div class="categories-prodlist__item categories-prodcard">
                        <div class="categories-prodcard__img">
                            <a href="{{route('product', $product->slug)}}">
                                <img src="{{url($product->firstImage->image)}}" alt="{{$product->title}}" loading="lazy">
                            </a>
                        </div>
                        <div class="categories-prodcard__title">
                            <a href="{{route('product', $product->slug)}}"><span>{{$product->title}}</span></a>
                        </div>
                        <div class="categories-prodcard__price prodcard-price">
                            <span class="prodcard-price__pretext">@lang('messages.price')</span>
                            {{$lng == 'uk' || $lng == 'ru' ?   $product->price : $product->price_euro}}
                            <span class="prodcard-price__curency">@lang('messages.currency')</span>
                        </div>
                        <ul class="categories-prodcard__attr prodcard-attr categories-item_hidden">

                            @foreach($product->productAttributes as $attr)
                                @if($attr->id == 2) @continue @endif
                            <li class="prodcard-attr__item">
                                <span class="prodcard-attr__lable">
                                @foreach($values as $value)
                                    @if($value['id'] == $attr->pivot->value){{$attr->name}}
                                </span>

                                        <span class="prodcard-attr__value">
                                            <img src="{!! $value['css_style'] !!}" style="width: 52px; height: 33px; padding-bottom: 5px; filter: none; float:left;" >
                                        </span>
                                    @endif
                                @endforeach
                            </li>
                            @endforeach
                        </ul>
                        <div class="categories-prodcard__btn categories-item_hidden">
                            <button class="btn-buy buy-but" data-toggle="modal"
                                    data-id="{{$product->id}}"
                                    data-title="{{$product->title}}"
                                    data-img="{{url($product->firstImage->image)}}"
                                    data-price="{{$lng == 'uk' || $lng == 'ru' ?   $product->price : $product->price_euro}}"
                                    data-url="{{route('cart.ajaxstore')}}"
                                    data-tp="@lang('messages.price')"
                                    data-tc="@lang('messages.currency')"
                                    data-target="#buy-button">@lang('messages.buy_button')</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<div class="section-categories__wrapper container">{!! $category->content !!}</div>
@endsection

