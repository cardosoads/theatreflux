@extends('layouts.app')

@section('title', 'Editor de Fluxos de Teatro')

@section('content')
<theatre-flow-editor 
    @if(isset($projectId))
    :initial-project-id="{{ $projectId }}"
    @endif
></theatre-flow-editor>
@endsection