function validateRegister() {
  // Get form inputs
  const username = document.getElementById('username').value;
  const email = document.getElementById('email').value;
  const phone = document.getElementById('phone').value;
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm-password').value;

  // Clear previous error messages
  document.getElementById('usernameError').textContent = '';
  document.getElementById('emailError').textContent = '';
  document.getElementById('phoneError').textContent = '';
  document.getElementById('passwordError').textContent = '';
  document.getElementById('confirmPasswordError').textContent = '';

  let isValid = true;

  // Validate Username
  if (username === '') {
      document.getElementById('usernameError').textContent = 'Username is required.';
      isValid = false;
  }

  // Validate Email
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
      document.getElementById('emailError').textContent = 'Please enter a valid email address.';
      isValid = false;
  }

  // Validate Phone Number (10 digits)
  const phonePattern = /^\d{10}$/;
  if (!phonePattern.test(phone)) {
      document.getElementById('phoneError').textContent = 'Please enter a valid 10-digit phone number.';
      isValid = false;
  }

  // Validate Password
  if (password.length < 8) {
      document.getElementById('passwordError').textContent = 'Password must be at least 8 characters long.';
      isValid = false;
  } else if (!/[A-Z]/.test(password)) {
      document.getElementById('passwordError').textContent = 'Password must contain at least one uppercase letter.';
      isValid = false;
  } else if (!/[a-z]/.test(password)) {
      document.getElementById('passwordError').textContent = 'Password must contain at least one lowercase letter.';
      isValid = false;
  } else if (!/[0-9]/.test(password)) {
      document.getElementById('passwordError').textContent = 'Password must contain at least one number.';
      isValid = false;
  } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
      document.getElementById('passwordError').textContent = 'Password must contain at least one special character.';
      isValid = false;
  }

  // Validate Confirm Password
  if (password !== confirmPassword) {
      document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
      isValid = false;
  }

  return isValid;
}
// Validate Login Form
function validateLogin() {
  const emailPhone = document.getElementById('loginEmailPhone').value;
  const password = document.getElementById('loginPassword').value;

  if (!emailPhone || !password) {
      alert('Please fill in all fields.');
      return false;
  }

  // Basic email/phone validation
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const phonePattern = /^\d{10}$/;

  if (!emailPattern.test(emailPhone) && !phonePattern.test(emailPhone)) {
      alert('Please enter a valid email or 10-digit phone number.');
      return false;
  }

  // Password length validation
  if (password.length < 6) {
      alert('Password must be at least 6 characters long.');
      return false;
  }

  return true;
}
//contact form
function validateContactForm() {
  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const message = document.getElementById('message').value;

  if (!name || !email || !message) {
      alert('Please fill in all fields.');
      return false;
  }

  // Basic email validation
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
      alert('Please enter a valid email address.');
      return false;
  }

  return true;
}