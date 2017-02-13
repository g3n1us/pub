@extends("pub::filemanager.core")

@section('filemanager_page', 'Files')

@section('body')

@include('pub::parts.foot_assets')
@include('pub::filemanager.filemanager_contents')
@include('pub::parts.editor')

@endsection