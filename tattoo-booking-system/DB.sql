-- Roles
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL -- 'Admin', 'User', 'Artist'
);

-- Insert default roles
INSERT INTO roles (id, name) VALUES 
(1, 'Admin'),
(2, 'User'),
(3, 'Artist');

-- Users
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    name VARCHAR(255),
    location VARCHAR(255),
    role_id INT NULL REFERENCES roles(id),
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE users 
ADD COLUMN email_confirmed BOOLEAN DEFAULT FALSE,
ADD COLUMN confirmation_token VARCHAR(100),
ADD COLUMN avatar VARCHAR(255) DEFAULT '/assets/images/default-avatar.png';

-- Artist Profile (One-to-One with User)
CREATE TABLE artist_profiles (
    id SERIAL PRIMARY KEY,
    user_id INT UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    style TEXT,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services offered by Artists
CREATE TABLE services (
    id SERIAL PRIMARY KEY,
    artist_id INT REFERENCES artist_profiles(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    duration INT NOT NULL DEFAULT 60 -- Duration in minutes
);

-- Bookings
CREATE TABLE bookings (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    artist_id INT REFERENCES artist_profiles(id),
    service_id INT REFERENCES services(id),
    appointment_time TIMESTAMP NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'requested' 
    CHECK (status IN ('requested', 'approved', 'denied', 'cancelled', 'completed', 'in_progress')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tattoo_id INT REFERENCES posts(id),
    notes TEXT,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    duration INT NOT NULL
);

-- Reviews
CREATE TABLE reviews (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    artist_id INT REFERENCES artist_profiles(id),
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Messages (User to User Chat)
CREATE TABLE messages (
    id SERIAL PRIMARY KEY,
    sender_id INT REFERENCES users(id),
    receiver_id INT REFERENCES users(id),
    content TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Followers (User can follow another user)
CREATE TABLE follows (
    id SERIAL PRIMARY KEY,
    follower_id INT REFERENCES users(id),
    following_id INT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(follower_id, following_id)
);

-- Payments (Artist Subscription or Booking)
CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    amount DECIMAL(10,2) NOT NULL,
    payment_type VARCHAR(50), -- 'artist_subscription', 'booking'
    related_booking_id INT REFERENCES bookings(id),
    status VARCHAR(50) DEFAULT 'pending', -- 'pending', 'completed', 'failed'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Memberships (Optional Artist Subscription)
CREATE TABLE memberships (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Artist Posts (like Instagram)
CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    artist_id INT REFERENCES artist_profiles(id) ON DELETE CASCADE,
    image_url TEXT NOT NULL,
    caption TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Likes on Posts
CREATE TABLE post_likes (
    id SERIAL PRIMARY KEY,
    post_id INT REFERENCES posts(id) ON DELETE CASCADE,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(post_id, user_id) -- one like per user per post
);

-- Comments on Posts
CREATE TABLE post_comments (
    id SERIAL PRIMARY KEY,
    post_id INT REFERENCES posts(id) ON DELETE CASCADE,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    comment TEXT NOT NULL,
    commented_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create tattoo progress table
CREATE TABLE tattoo_progress (
    id SERIAL PRIMARY KEY,
    booking_id INT REFERENCES bookings(id) ON DELETE CASCADE,
    status VARCHAR(50) NOT NULL DEFAULT 'not_started'
    CHECK (status IN ('not_started', 'in_progress', 'completed')),
    notes TEXT,
    progress_percentage INT CHECK (progress_percentage BETWEEN 0 AND 100),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Make sure this table exists and has the correct structure
CREATE TABLE IF NOT EXISTS artist_working_hours (
    id SERIAL PRIMARY KEY,
    artist_id INT REFERENCES artist_profiles(id) ON DELETE CASCADE,
    day_of_week INT CHECK (day_of_week BETWEEN 1 AND 7),
    start_time TIME,
    end_time TIME,
    is_working BOOLEAN DEFAULT true,
    UNIQUE(artist_id, day_of_week)
);

-- Make sure artist_profiles table doesn't have working_hours column
ALTER TABLE artist_profiles
DROP COLUMN IF EXISTS working_hours;

-- Create holidays table
CREATE TABLE holidays (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    UNIQUE(date)
);

-- Insert Belgian holidays for 2024/2025
INSERT INTO holidays (name, date) VALUES
('New Year''s Day', '2024-01-01'),
('Easter Monday', '2024-04-01'),
('Labor Day', '2024-05-01'),
('Ascension Day', '2024-05-09'),
('Whit Monday', '2024-05-20'),
('National Day', '2024-07-21'),
('Assumption Day', '2024-08-15'),
('All Saints'' Day', '2024-11-01'),
('Armistice Day', '2024-11-11'),
('Christmas Day', '2024-12-25'),
('New Year''s Day', '2025-01-01'),
('Easter Monday', '2025-04-21'),
('Labor Day', '2025-05-01'),
('Ascension Day', '2025-05-29'),
('Whit Monday', '2025-06-09'),
('National Day', '2025-07-21'),
('Assumption Day', '2025-08-15'),
('All Saints'' Day', '2025-11-01'),
('Armistice Day', '2025-11-11'),
('Christmas Day', '2025-12-25');
