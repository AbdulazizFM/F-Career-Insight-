/* ==========================================
   Authentication System
   ========================================== */

// Check if user is authenticated
function isAuthenticated() {
    const user = localStorage.getItem('currentUser');
    return user !== null;
}

// Get current user
function getCurrentUser() {
    const userStr = localStorage.getItem('currentUser');
    return userStr ? JSON.parse(userStr) : null;
}

// Check if user is admin
function isAdmin() {
    const user = getCurrentUser();
    return user && user.role === 'admin';
}

// Login function
function login(email, password) {
    // For demo purposes - in production, this would call an API
    // Check if user exists in localStorage or use default admin/demo users
    
    // Default admin credentials
    if (email === 'admin@careerinsights.sa' && password === 'admin123') {
        const adminUser = {
            id: 'admin1',
            name: 'Admin User',
            email: email,
            role: 'admin'
        };
        localStorage.setItem('currentUser', JSON.stringify(adminUser));
        return { success: true, user: adminUser };
    }
    
    // Check registered users
    const users = JSON.parse(localStorage.getItem('registeredUsers') || '[]');
    const user = users.find(u => u.email === email && u.password === password);
    
    if (user) {
        const userData = {
            id: user.id,
            name: user.name,
            email: user.email,
            role: user.role || 'user'
        };
        localStorage.setItem('currentUser', JSON.stringify(userData));
        return { success: true, user: userData };
    }
    
    // Default demo user
    if (email === 'user@demo.com' && password === 'demo123') {
        const demoUser = {
            id: 'user1',
            name: 'Demo User',
            email: email,
            role: 'user'
        };
        localStorage.setItem('currentUser', JSON.stringify(demoUser));
        return { success: true, user: demoUser };
    }
    
    return { success: false, error: 'Invalid email or password' };
}

// Register function
function register(name, email, password) {
    // Get existing users
    const users = JSON.parse(localStorage.getItem('registeredUsers') || '[]');
    
    // Check if email already exists
    if (users.some(u => u.email === email)) {
        return { success: false, error: 'Email already registered' };
    }
    
    // Create new user
    const newUser = {
        id: 'user' + Date.now(),
        name: name,
        email: email,
        password: password, // In production, this should be hashed
        role: 'user',
        createdAt: new Date().toISOString()
    };
    
    // Add to users array
    users.push(newUser);
    localStorage.setItem('registeredUsers', JSON.stringify(users));
    
    // Auto login
    const userData = {
        id: newUser.id,
        name: newUser.name,
        email: newUser.email,
        role: newUser.role
    };
    localStorage.setItem('currentUser', JSON.stringify(userData));
    
    return { success: true, user: userData };
}

// Logout function
function logout() {
    localStorage.removeItem('currentUser');
    window.location.href = '../index.html';
}

// Protect page - redirect if not authenticated
function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = 'login.html';
    }
}

// Protect admin page
function requireAdmin() {
    if (!isAuthenticated()) {
        window.location.href = 'login.html';
        return;
    }
    if (!isAdmin()) {
        window.location.href = 'user-dashboard.html';
    }
}

// Redirect if already logged in
function redirectIfAuthenticated() {
    if (isAuthenticated()) {
        const user = getCurrentUser();
        if (user.role === 'admin') {
            window.location.href = 'admin-dashboard.html';
        } else {
            window.location.href = 'user-dashboard.html';
        }
    }
}

// Check subscription status
function hasActiveSubscription() {
    try {
        const subscriptionData = localStorage.getItem('userSubscription');
        if (subscriptionData) {
            const subscription = JSON.parse(subscriptionData);
            const now = new Date();
            const expiryDate = new Date(subscription.expiryDate);
            return subscription.active && expiryDate > now;
        }
    } catch (e) {
        console.error('Error reading subscription:', e);
    }
    return false;
}

// Get purchased jobs
function getPurchasedJobs() {
    try {
        const purchasedJobs = localStorage.getItem('purchasedJobs');
        return purchasedJobs ? JSON.parse(purchasedJobs) : [];
    } catch (e) {
        console.error('Error reading purchased jobs:', e);
        return [];
    }
}

// Check if user has access to a job
function hasJobAccess(jobId) {
    return hasActiveSubscription() || getPurchasedJobs().includes(jobId);
}

// Purchase subscription
function purchaseSubscription() {
    const subscriptionData = {
        active: true,
        startDate: new Date().toISOString(),
        expiryDate: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString(),
        plan: 'monthly',
        price: 29
    };
    localStorage.setItem('userSubscription', JSON.stringify(subscriptionData));
}

// Purchase single job
function purchaseJob(jobId) {
    const purchasedJobs = getPurchasedJobs();
    if (!purchasedJobs.includes(jobId)) {
        purchasedJobs.push(jobId);
        localStorage.setItem('purchasedJobs', JSON.stringify(purchasedJobs));
    }
}
