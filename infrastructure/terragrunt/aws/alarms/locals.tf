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
    "action=lostpassword&error",
    "GET /notification-gc-notify/wp-json/wp/v2/pages",
    "HTTP/1.1\\\" 404",
  ]
  wordpress_warnings = [
    "Warning",
    "warning",
  ]
  wordpress_warnings_skip = [
    "Undefined array key*c3-cloudfront-clear-cache",
  ]
  wordpress_error_metric_pattern   = "[(w1=\"*${join("*\" || w1=\"*", local.wordpress_errors)}*\") && w1!=\"*${join("*\" && w1!=\"*", local.wordpress_errors_skip)}*\"]"
  wordpress_warning_metric_pattern = "[(w1=\"*${join("*\" || w1=\"*", local.wordpress_warnings)}*\") && w1!=\"*${join("*\" && w1!=\"*", local.wordpress_warnings_skip)}*\"]"
}