# Bus Booking Backend API Documentation

## Authentication

### Register
- URL: `POST /api/register`
- Auth: None
- Request body:
  - `name` (string, required)
  - `email` (string, required, unique)
  - `password` (string, required, min 8)
  - `password_confirmation` (string, required)
- Response: 201 Created
  - `message`
  - `user`
  - `access_token`
  - `token_type`

### Login
- URL: `POST /api/login`
- Auth: None
- Request body:
  - `email` (string, required)
  - `password` (string, required)
- Response: 200 OK
  - `message`
  - `user`
  - `access_token`
  - `token_type`

### Logout
- URL: `POST /api/logout`
- Auth: Bearer token
- Response: 200 OK
  - `message`

### Get Authenticated User
- URL: `GET /api/user`
- Auth: Bearer token
- Response: 200 OK
  - authenticated user object

## Authorization

Protected endpoints require `Authorization: Bearer <token>`.

## Buses
All bus endpoints require authentication.

### List Buses
- URL: `GET /api/buses`
- Response: 200 OK
  - list of buses with nested `seats`

### Create Bus
- URL: `POST /api/buses`
- Request body:
  - `bus_number` (string, required, unique)
  - `type` (string, required)
  - `total_seats` (integer, required, min 1)
- Response: 201 Created
  - `message`
  - `bus`

### Get Bus
- URL: `GET /api/buses/{bus}`
- Response: 200 OK
  - `bus` with nested `seats`

### Update Bus
- URL: `PUT /api/buses/{bus}`
- Request body: any bus fields to update
- Response: 200 OK
  - `message`
  - `bus`

### Delete Bus
- URL: `DELETE /api/buses/{bus}`
- Response: 200 OK
  - `message`

## Routes
All route endpoints require authentication.

### List Routes
- URL: `GET /api/routes`
- Response: 200 OK
  - list of route objects

### Create Route
- URL: `POST /api/routes`
- Request body:
  - `origin` (string, required)
  - `destination` (string, required)
  - `price` (numeric, required, min 0)
  - `departure_time` (required)
  - `duration_minutes` (integer, required, min 1)
- Response: 201 Created
  - `message`
  - `route`

### Get Route
- URL: `GET /api/routes/{route}`
- Response: 200 OK
  - `route`

### Update Route
- URL: `PUT /api/routes/{route}`
- Request body: any route fields to update
- Response: 200 OK
  - `message`
  - `route`

### Delete Route
- URL: `DELETE /api/routes/{route}`
- Response: 200 OK
  - `message`

## Seats
All seat endpoints require authentication.

### List Seats
- URL: `GET /api/seats`
- Query params (optional):
  - `bus_id` (filter by bus)
  - `route_id` (filter by route)
  - `available` (boolean, filter by `is_available`)
- Response: 200 OK
  - `message`
  - `data`

### Create Seat
- URL: `POST /api/seats`
- Request body:
  - `bus_id` (required, exists in buses)
  - `route_id` (required, exists in routes)
  - `seat_number` (required, unique)
  - `is_available` (boolean)
- Response: 201 Created
  - `message`
  - `data`

### Get Seat
- URL: `GET /api/seats/{seat}`
- Response: 200 OK
  - `message`
  - `data`

### Update Seat
- URL: `PUT /api/seats/{seat}`
- Request body:
  - `seat_number` (string)
  - `is_available` (boolean)
- Response: 200 OK
  - `message`
  - `data`

### Delete Seat
- URL: `DELETE /api/seats/{seat}`
- Response: 200 OK
  - `message`

## Bookings
All booking endpoints require authentication.

### List Current User Bookings
- URL: `GET /api/bookings`
- Response: 200 OK
  - list of current user bookings with `route`, `bus`, and `seat`

### Create Booking
- URL: `POST /api/bookings`
- Request body:
  - `route_id` (required, exists in routes)
  - `bus_id` (required, exists in buses)
  - `seat_id` (required, exists in seats)
  - `travel_date` (required, date, today or later)
  - `amount_paid` (required, numeric, min 0)
- Response: 201 Created
  - `message`
  - `booking`
- Notes:
  - seat availability is checked
  - if booking succeeds, seat is marked unavailable

### Get Booking
- URL: `GET /api/bookings/{booking}`
- Response: 200 OK
  - booking with `route`, `bus`, and `seat`

### Update Booking
- URL: `PUT /api/bookings/{booking}`
- Request body:
  - `status`
- Response: 200 OK
  - `message`
  - `booking`

### Delete Booking
- URL: `DELETE /api/bookings/{booking}`
- Response: 200 OK
  - `message`
  - note: deletes the booking and marks seat available again

### Get User Booking History
- URL: `GET /api/my-bookings`
- Response: 200 OK
  - `message`
  - `data`

## Branches
All branch endpoints require authentication.

### List Branches
- URL: `GET /api/admin/branches`
- Response: 200 OK
  - list of branches with users

### Create Branch
- URL: `POST /api/admin/branches`
- Request body:
  - `name` (required)
  - `location` (required)
- Response: 201 Created
  - `message`
  - `branch`

### Get Branch
- URL: `GET /api/admin/branches/{branch}`
- Response: 200 OK
  - branch with `users` and `bookings`

### Update Branch
- URL: `PUT /api/admin/branches/{branch}`
- Response: 200 OK
  - `message`
  - `branch`

### Delete Branch
- URL: `DELETE /api/admin/branches/{branch}`
- Response: 200 OK
  - `message`

## Admin / Analytics
All admin endpoints require authentication.

### Get All Bookings
- URL: `GET /api/admin/bookings`
- Response: 200 OK
  - list of all bookings with related user, route, bus, seat, and branch

### Get Global Stats
- URL: `GET /api/admin/stats`
- Response: 200 OK
  - `total_bookings`
  - `total_users`
  - `total_branches`
  - `total_revenue`

### Get Sub-admins
- URL: `GET /api/admin/sub-admins`
- Response: 200 OK
  - list of `sub_admin` users with branch relation

### Create Sub-admin
- URL: `POST /api/admin/sub-admins`
- Request body:
  - `name` (required)
  - `email` (required, unique)
  - `password` (required, min 8)
  - `branch_id` (required, exists in branches)
- Response: 201 Created
  - `message`
  - `user`

### Get Branch Bookings (Sub Admin)
- URL: `GET /api/branch/bookings`
- Response: 200 OK
  - bookings for the authenticated sub-admin's branch

## Notes
- All protected endpoints are grouped under `auth:sanctum` middleware.
- The API uses Laravel Sanctum token authentication. Send the bearer token as:
  - `Authorization: Bearer <token>`
- If you want to create an API client, call `/api/login` or `/api/register` first, then use the returned `access_token`.
- The API does not currently require role-based checks in code, so protect super-admin and sub-admin routes through middleware or guard logic if needed.
