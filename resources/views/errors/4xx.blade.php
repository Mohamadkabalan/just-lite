@extends('errors::layout')

{{-- Any 4xx status without its own view, e.g. 405. --}}
@section('code', isset($exception) ? (string) $exception->getStatusCode() : '400')
