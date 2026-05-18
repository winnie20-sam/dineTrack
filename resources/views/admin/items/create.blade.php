@extends('adminlte::page')

@section('title', 'Add Item')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Add Menu Item</h1>
        <a href="{{ route('admin.items.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.items.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Business</label>
                    <select name="business_id" class="form-control @error('business_id') is-invalid @enderror">
                        <option value="">-- Select Business --</option>
                        @foreach($businesses as $business)
                            <option value="{{ $business->id }}" {{ old('business_id') == $business->id ? 'selected' : '' }}>
                                {{ $business->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('business_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Item name">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control @error('category') is-invalid @enderror">
                        <option value="">-- Select Category --</option>
                        <option value="Food" {{ old('category') == 'Food' ? 'selected' : '' }}>Food</option>
                        <option value="Drinks" {{ old('category') == 'Drinks' ? 'selected' : '' }}>Drinks</option>
                        <option value="Dessert" {{ old('category') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                        <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Price (KES)</label>
                    <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="0.00">
                    @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Item
                </button>
            </form>
        </div>
    </div>
@endsection
