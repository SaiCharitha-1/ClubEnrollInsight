from django.urls import path
from . import views

urlpatterns = [
    path('signup/', views.signup, name='signup'),
    path('login/', views.login, name='login'),  # Add this line for the login page
    # Other URL patterns for your project...
]
