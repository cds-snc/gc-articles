locals {
  # WordPress ECS task metric filters.  These are used to create CloudWatch alarms based on the number of
  # errors/warnings in the logs, as identified by the tokens.  Any log message that has a token in the `_skip` list
  # will not be counted as an error/warning.
  wordpress_errors = [
    "Failed",
    "failed",
    "Error",
    "error",
    "Fatal",
    "fatal",
  ]
  wordpress_errors_skip = [
    "AH01276",
    "AH01630",
    "AH01797",
    "action=lostpassword&error",
    "Cron unschedule event error for hook",
    "database error",
    "GET /notification-gc-notify/wp-json/wp/v2/pages",
    "getaddrinfo for*proxy*failed",
    "HTTP/1.1\\\" 301",
    "HTTP/1.1\\\" 400",
    "HTTP/1.1\\\" 403",
    "HTTP/1.1\\\" 404",
    "icon_error.gif HTTP/1.1\\\" 200",
    "Undefined constant",
    "value_error.email",
    "/usr/src/wordpress/wp-content/languages",
  ]
  wordpress_database_errors = [
    "database error",
    "getaddrinfo for*proxy*failed",
  ]
  wordpress_warnings = [
    "Cron unschedule event error for hook",
    "Warning",
    "warning",
  ]
  wordpress_warnings_skip = [
    "Attempt to read property*class-wp-rest-templates-controller.php",
    "Undefined array key*c3-cloudfront-clear-cache",
    "/usr/src/wordpress/wp-content/languages",
    "chmod()",
  ]
  wordpress_error_metric_pattern          = "[(w1=\"*${join("*\" || w1=\"*", local.wordpress_errors)}*\") && w1!=\"*${join("*\" && w1!=\"*", local.wordpress_errors_skip)}*\"]"
  wordpress_database_error_metric_pattern = "[(w1=\"*${join("*\" || w1=\"*", local.wordpress_database_errors)}*\")]"
  wordpress_warning_metric_pattern        = "[(w1=\"*${join("*\" || w1=\"*", local.wordpress_warnings)}*\") && w1!=\"*${join("*\" && w1!=\"*", local.wordpress_warnings_skip)}*\"]"
}