from django.db import models

class User(models.Model):
    email = models.EmailField()
    first_name = models.CharField(max_length=100)
    last_name = models.CharField(max_length=100)
    phone = models.CharField(max_length=20)
    user_id = models.CharField(max_length=50)
    password = models.CharField(max_length=50)
