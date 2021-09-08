from locust import HttpUser, task

class AdminUser(HttpUser):
    @task
    def run_flow(self):
        # No, login - all handled by CloudFront
        self.client.get(f"/")
        self.client.get(f"/hello-world")
        self.client.get(f"/?s=hello")

        # To test login flow, create a `locust` user in WordPress
        response = self.client.post("/wp-login.php", {"log":"locust", "pwd":"locust", "testcookie": "1"})
        response = self.client.get("/wp-admin/profile.php")
