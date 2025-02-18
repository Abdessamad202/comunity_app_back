# Community App - API Documentation

This is the API documentation for the **Community App**. The app provides authentication functionality, user registration, profile management, and login/logout features.

## Routes Overview

### 1. **Authenticated Routes** (Requires Sanctum Authentication)
These routes are protected and require the user to be authenticated via Sanctum.

- **GET /user**
  - Returns the currently authenticated user's data.
  - **Middleware**: `auth:sanctum`

- **GET /profile/{profile}**
  - Displays the profile information for a given profile ID.
  - **Controller**: `ProfileController@show`

### 2. **Guest Routes**
These routes are accessible to guests (unauthenticated users) and handle the registration process, login, and logout.

#### Register Routes

- **POST /register/step-1**
  - Starts the user registration process. This step will collect sensitive data.
  - **Controller**: `Authentication@registerStep1`

- **POST /register/resend-code/{user}**
  - Resends the email verification code to the user for email verification.
  - **Controller**: `Authentication@resendCode`

- **POST /register/step-2/{user}**
  - Collects the second step of registration (user verification).
  - **Controller**: `Authentication@registerStep2`

- **POST /register/step-3/{user}**
  - Collects the final profile information for registration.
  - **Controller**: `Authentication@registerStep3`

#### Login and Logout Routes

-
# comunity_app_back
