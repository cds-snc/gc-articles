import os
from locust import HttpUser, task, between

USER = os.getenv("USER")
PASSWORD = os.getenv("PASSWORD")

class AdminUser(HttpUser):
    wait_time = between(1, 3)

    @task(20)
    def no_login(self):
        "No login - all handled by CloudFront"
        self.client.get(f"/")
        self.client.get(f"/avis-de-confidentialite")
        self.client.get(f"/confirmation-of-subscription")

    @task
    def login(self):
        "Login flow - not cached"
        response = self.client.post("/login/", {"log":USER, "pwd":PASSWORD, "testcookie": "1", "redirect_to": "https://ircc.digital.canada.ca/wp-admin/admin.php?page=cds_notify_send"})
        self.client.get("/wp-admin/edit.php", cookies=response.cookies)
