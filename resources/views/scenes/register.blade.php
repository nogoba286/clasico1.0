@extends('app')
@section('content')
<div class="register">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="text-center mb-3">
                <i class="bi bi-person-plus-fill text-warning"></i> Register
            </h3>
            <p class="text-center">All fields are mandatory</p>
            <form method="POST" action="{{ route('register') }}">
                @csrf <!-- CSRF token for form security -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Repeat Password *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Repeat password" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">First name *</label>
                        <input type="text" class="form-control" name="first_name" placeholder="First name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last name *</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Last name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Birthday *</label>
                        <input type="date" class="form-control" name="birthday" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Country *</label>
                        <select class="form-select" name="country" required>
                            <option selected disabled>Select your country</option>
                            <option>United States</option>
                            <option>United Kingdom</option>
                            <option>Canada</option>
                            <option>Australia</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City *</label>
                        <input type="text" class="form-control" name="city" placeholder="City" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Zip Code *</label>
                        <input type="text" class="form-control" name="zip_code" placeholder="Your zip code" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address *</label>
                        <input type="text" class="form-control" name="address" placeholder="Street" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number *</label>
                        <div class="input-group">
                            <span class="input-group-text">+234</span>
                            <input type="text" class="form-control" name="phone_number" placeholder="Phone number" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Currency *</label>
                        <select class="form-select" name="currency" required>
                            <option selected disabled>Select currency</option>
                            <option>USD</option>
                            <option>EUR</option>
                            <option>GBP</option>
                            <option>NGN</option>
                        </select>
                    </div>
                    <div class="col-md-12 form-check">
                        <input type="checkbox" class="form-check-input" name="terms_and_conditions" required>
                        <label class="form-check-label">
                            I'm over 18 and accept the 
                            <a href="#" class="text-primary">Terms and Conditions</a> 
                            and the 
                            <a href="#" class="text-primary">Privacy Policy</a>.
                        </label>
                    </div>
                    <div class="col-md-12 form-check">
                        <input type="checkbox" class="form-check-input" name="customized_offers">
                        <label class="form-check-label">
                            Get customized offers/bonuses via email.
                        </label>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-warning w-100">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
