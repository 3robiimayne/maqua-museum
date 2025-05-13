<?php
require_once 'includes/dbconn.inc.php';
require_once 'includes/header.inc.php';

// Check if user is already logged in
if (isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

// Check for error messages
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Google OAuth configuration
$googleClientId = "YOUR_GOOGLE_CLIENT_ID"; // Replace with actual client ID
$googleRedirectUri = "https://yourdomain.com/google-callback.php"; // Replace with actual redirect URI
?>

<!-- Register Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden animate__animated animate__fadeIn">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-center mb-6">Create an Account</h2>
                
                <?php if($error): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <?php 
                                    switch($error) {
                                        case 'emptyfields':
                                            echo 'Please fill in all fields.';
                                            break;
                                        case 'invalidmail':
                                            echo 'Please enter a valid email address.';
                                            break;
                                        case 'passwordcheck':
                                            echo 'Passwords do not match.';
                                            break;
                                        case 'emailtaken':
                                            echo 'Email is already registered.';
                                            break;
                                        default:
                                            echo 'An error occurred. Please try again.';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form action="includes/register.inc.php" method="post" id="register-form" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                        <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long.</p>
                    </div>
                    
                    <div>
                        <label for="password-repeat" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="password-repeat" name="password-repeat" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                    </div>
                    
                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" required>
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            I agree to the <a href="#" class="text-primary hover:text-primary-dark">Terms and Conditions</a>
                        </label>
                    </div>
                    
                    <div>
                        <button type="submit" name="register-submit" class="w-full btn-primary py-2">
                            Create Account
                        </button>
                    </div>
                </form>
                
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                Or continue with
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <a href="<?php echo 'https://accounts.google.com/o/oauth2/v2/auth?scope=email%20profile&access_type=offline&include_granted_scopes=true&response_type=code&state=state_parameter_passthrough_value&redirect_uri='.$googleRedirectUri.'&client_id='.$googleClientId; ?>" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                                <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                                    <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                                    <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"/>
                                    <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                                    <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
                                </g>
                            </svg>
                            Sign up with Google
                        </a>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="login.php" class="font-medium text-primary hover:text-primary-dark">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Register Form Validation -->
<script>
$(document).ready(function() {
    $('#register-form').submit(function(e) {
        const name = $('#name').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const passwordRepeat = $('#password-repeat').val();
        const terms = $('#terms').is(':checked');
        let isValid = true;
        
        // Simple validation
        if (!name || !email || !password || !passwordRepeat) {
            isValid = false;
            alert('Please fill in all fields');
        } else if (password.length < 8) {
            isValid = false;
            alert('Password must be at least 8 characters long');
        } else if (password !== passwordRepeat) {
            isValid = false;
            alert('Passwords do not match');
        } else if (!terms) {
            isValid = false;
            alert('You must agree to the Terms and Conditions');
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>

<?php require_once 'includes/footer.inc.php'; ?>