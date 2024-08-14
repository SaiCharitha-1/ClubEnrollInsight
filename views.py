from django.shortcuts import render, redirect
from .forms import UserForm
from django.urls import reverse

def signup(request):
    if request.method == 'POST':
        form = UserForm(request.POST)
        if form.is_valid():
            form.save()  # Save the user data to the database
            # Redirect to the login page after successful signup
            return redirect(reverse('login'))  # Assuming the name of the login URL pattern is 'login'
    else:
        form = UserForm()
    return render(request, 'signup.html', {'form': form})

from django.shortcuts import render
from django.http import HttpResponse

def login(request):
    if request.method == 'POST':
        # Handle login form submission
        # Logic to authenticate user
        return HttpResponse('Login successful!')  # Replace this with your logic
    else:
        # Render the login form
        return render(request, 'login.html')
