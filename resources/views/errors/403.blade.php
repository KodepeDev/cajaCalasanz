@extends('errors::minimal')

@section('title', 'No tienes permiso para acceder a esta página.')
@section('code', '403')
@section('message', 'No tienes permiso para acceder a esta página.')
{{-- @section('message', $exception->getMessage() ?: 'No tienes permiso para acceder a esta página.') --}}
