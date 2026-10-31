@extends('errors::layout')

{{-- Any 5xx status without its own view. --}}
@section('code', isset($exception) ? (string) $exception->getStatusCode() : '500')
