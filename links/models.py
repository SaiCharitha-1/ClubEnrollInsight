from django.db import models

class User(models.Model):
    email = models.EmailField(unique=True)
    first_name = models.CharField(max_length=100)
    last_name = models.CharField(max_length=100)
    phone = models.CharField(max_length=15)
    user_id = models.CharField(max_length=100, unique=True)
    password = models.CharField(max_length=100)
    # Add other fields as needed
