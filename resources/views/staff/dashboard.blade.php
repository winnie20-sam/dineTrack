@extends('adminlte::page')

@section('title', 'Staff Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Welcome, {{ auth()->user()->name }}</h1>
        <span class="badge badge-primary badge-pill px-3 py-2">
            <i class="fas fa-store mr-1"></i> {{ $staff->business->name }}
        </span>
    </div>
@stop

@section('content')

    <div class="row mb-4">
        <div class="col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-receipt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Sales Count</span>
                    <span class="info-box-number">{{ $todayCount }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Total</span>
                    <span class="info-box-number">KES {{ number_format($todayTotal, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-chart-bar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Sales (All Time)</span>
                    <span class="info-box-number">{{ $totalSales }}</span>
                </div>
            </div>
        </div>
    </div>

@stop
