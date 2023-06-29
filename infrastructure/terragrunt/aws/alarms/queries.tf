resource "aws_cloudwatch_query_definition" "wordpress_errors" {
  name = "Wordpress - errors"

  log_group_names = [
    var.wordpress_log_group_name
  ]

  query_string = <<-QUERY
    fields @timestamp, @message, @logStream
    | filter @message like /(?i)error|failed|fatal/
    | sort @timestamp desc
    | limit 100
  QUERY
}

resource "aws_cloudwatch_query_definition" "wordpress_warnings" {
  name = "Wordpress - warnings"

  log_group_names = [
    var.wordpress_log_group_name
  ]

  query_string = <<-QUERY
    fields @timestamp, @message, @logStream
    | filter @message like /(?i)warning/
    | sort @timestamp desc
    | limit 100
  QUERY
}

resource "aws_cloudwatch_query_definition" "wordpress_failed_logins" {
  name = "Wordpress - failed logins"

  log_group_names = [
    var.wordpress_log_group_name
  ]

  query_string = <<-QUERY
    fields @timestamp, @message, @logStream
    | filter @message like /LOGIN FAILED/
    | sort @timestamp desc
    | limit 100
  QUERY
}
