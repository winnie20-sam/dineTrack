@extends('adminlte::page')

@section('title', 'Edit Item')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Edit Menu Item</h1>
        <a href="{{ route('admin.items.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.items.update', $item) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Business</label>
                    <select name="business_id" class="form-control @error('business_id') is-invalid @enderror">
                        <option value="">-- Select Business --</option>
                        @foreach($businesses as $business)
                            <option value="{{ $business->id }}" {{ old('business_id', $item->business_id) == $business->id ? 'selected' : '' }}>
                                {{ $business->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('business_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Item Code</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $item->code) }}">
                    @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $item->name) }}">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control @error('category') is-invalid @enderror">
                        <option value="">-- Select Category --</option>
                        <option value="Food" {{ old('category', $item->category) == 'Food' ? 'selected' : '' }}>Food</option>
                        <option value="Drinks" {{ old('category', $item->category) == 'Drinks' ? 'selected' : '' }}>Drinks</option>
                        <option value="Dessert" {{ old('category', $item->category) == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                        <option value="Other" {{ old('category', $item->category) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Price (KES)</label>
                    <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $item->price) }}">
                    @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status_id" class="form-control @error('status_id') is-invalid @enderror">
                        <option value="">-- Select Status --</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ old('status_id', $item->status_id) == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Item
                </button>
            </form>
        </div>
    </div>
@endsection
