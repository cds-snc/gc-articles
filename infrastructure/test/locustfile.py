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
        self.client.get(f"/accessibility-statement")

    @task(5)
    def login(self):
        "Login flow - not cached"
        self.client.get(f"/sign-in-se-connecter")
        self.client.post("/sign-in-se-connecter", {"log":USER, "pwd":PASSWORD, "testcookie": "1", "redirect_to": "https://articles.cdssandbox.xyz/wp-admin/index.php"})
