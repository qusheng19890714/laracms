{{-- title:系统首页 --}}
@extends('core::layouts.master')

@section('content')
    <div class="d-flex full-height">        
                
        <div class="jumbotron bg-primary text-white full-width align-self-center text-center pos-r">           
            <h1>{{config('zotop.name')}}</h1>
            <p>{{config('zotop.description')}}</p>
            <div class="p-3">
                <a href="{{config('zotop.homepage')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-globe fa-fw"></i> Homepage
                </a>
                 
                <a href="javascript:;" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-circle-o fa-fw"></i> {{config('zotop.version')}}
                </a>
                 
                <a href="{{config('zotop.github')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-github fa-fw"></i> Github
                </a>
                 
                <a href="{{config('zotop.document')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-book fa-fw"></i> Document
                </a>                
                 
                <a href="{{config('zotop.help')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-question-circle fa-fw"></i> Help
                </a>
            </div>
            <svg class="animation-wave" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
                </defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="50" y="0" fill="rgba(255,255,255,.5)"></use>
                    <use xlink:href="#gentle-wave" x="50" y="3" fill="rgba(255,255,255,.5)"></use>
                    <use xlink:href="#gentle-wave" x="50" y="6" fill="rgba(255,255,255,.5)"></use>
                </g>
            </svg>             
        </div>

    </div>

@endsection

@push('css')

@endpush
