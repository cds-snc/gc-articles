# Performance testing
Uses [Locust](https://docs.locust.io/) to run performance tests.  

```sh
pip install -r requirement.txt
locust
```

:warning: If you want to test the WordPress login flow, export the username and password for the login:
```sh
export USER=someuser
export PASSWORD=somepassword
```