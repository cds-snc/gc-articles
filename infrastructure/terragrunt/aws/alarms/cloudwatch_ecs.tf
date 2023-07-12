#
# ECS: CPU/memory use
#
resource "aws_cloudwatch_metric_alarm" "ecs_cpu_utilization" {
  alarm_name          = "ECSCpuUtilization"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "2"
  metric_name         = "CPUUtilization"
  namespace           = "AWS/ECS"
  period              = "120"
  statistic           = "Average"
  threshold           = var.ecs_cpu_maximum
  treat_missing_data  = "notBreaching"

  alarm_description = "High ECS CPU utilization over 4 minutes"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    ClusterName = var.ecs_cluster_name
    ServiceName = var.ecs_service_name
  }
}

resource "aws_cloudwatch_metric_alarm" "ecs_memory_utilization" {
  alarm_name          = "ECSMemoryUtilization"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = 2
  metric_name         = "MemoryUtilization"
  namespace           = "AWS/ECS"
  period              = "120"
  statistic           = "Average"
  threshold           = var.ecs_memory_maximum
  treat_missing_data  = "notBreaching"

  alarm_description = "High Wordpress ECS memory utilization over 4 minutes"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    ClusterName = var.ecs_cluster_name
    ServiceName = var.ecs_service_name
  }
}

resource "aws_cloudwatch_log_metric_filter" "wordpress_failed_login" {
  name           = "WordPressFailedLogin"
  pattern        = "LOGIN FAILED"
  log_group_name = var.wordpress_log_group_name

  metric_transformation {
    name          = "WordPressFailedLogin"
    namespace     = "WordPress"
    value         = "1"
    default_value = "0"
  }
}

resource "aws_cloudwatch_metric_alarm" "wordpress_failed_login" {
  alarm_name          = "WordPressFailedLogin"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = aws_cloudwatch_log_metric_filter.wordpress_failed_login.metric_transformation[0].name
  namespace           = aws_cloudwatch_log_metric_filter.wordpress_failed_login.metric_transformation[0].namespace
  period              = "60"
  statistic           = "Sum"
  threshold           = var.wordpress_failed_login_maximum
  treat_missing_data  = "notBreaching"

  alarm_description = "High number of failed Wordpress login attempts in a 1 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]
}

resource "aws_cloudwatch_log_metric_filter" "wordpress_errors" {
  name           = "WordPressErrors"
  pattern        = local.wordpress_error_metric_pattern
  log_group_name = var.wordpress_log_group_name

  metric_transformation {
    name          = "WordPressErrors"
    namespace     = "WordPress"
    value         = "1"
    default_value = "0"
  }
}

resource "aws_cloudwatch_metric_alarm" "wordpress_errors" {
  alarm_name          = "WordPressErrors"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = aws_cloudwatch_log_metric_filter.wordpress_errors.metric_transformation[0].name
  namespace           = aws_cloudwatch_log_metric_filter.wordpress_errors.metric_transformation[0].namespace
  period              = "60"
  statistic           = "Sum"
  threshold           = "0"
  treat_missing_data  = "notBreaching"

  alarm_description = "WordPress errors detected in a 1 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]
}

resource "aws_cloudwatch_log_metric_filter" "wordpress_warnings" {
  name           = "WordPressWarnings"
  pattern        = local.wordpress_warning_metric_pattern
  log_group_name = var.wordpress_log_group_name

  metric_transformation {
    name          = "WordPressWarnings"
    namespace     = "WordPress"
    value         = "1"
    default_value = "0"
  }
}

resource "aws_cloudwatch_metric_alarm" "wordpress_warnings" {
  alarm_name          = "WordPressWarnings"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = aws_cloudwatch_log_metric_filter.wordpress_warnings.metric_transformation[0].name
  namespace           = aws_cloudwatch_log_metric_filter.wordpress_warnings.metric_transformation[0].namespace
  period              = "60"
  statistic           = "Sum"
  threshold           = "10"
  treat_missing_data  = "notBreaching"

  alarm_description = "WordPress warnings detected in a 1 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]
}

resource "aws_cloudwatch_log_metric_filter" "wordpress_ecs_warn_error_event" {
  name           = "WordPressEcsWarningEvent"
  pattern        = "?Warn ?Error"
  log_group_name = var.ecs_event_log_group_name

  metric_transformation {
    name          = "WordPressEcsWarnErrorEvent"
    namespace     = "WordPress"
    value         = "1"
    default_value = "0"
  }
}

resource "aws_cloudwatch_metric_alarm" "wordpress_ecs_warn_error_event" {
  alarm_name          = "WordPressEcsWarnErrorEvent"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = aws_cloudwatch_log_metric_filter.wordpress_ecs_warn_error_event.metric_transformation[0].name
  namespace           = aws_cloudwatch_log_metric_filter.wordpress_ecs_warn_error_event.metric_transformation[0].namespace
  period              = "60"
  statistic           = "Sum"
  threshold           = "0"
  treat_missing_data  = "notBreaching"

  alarm_description = "WordPress ECS warning or error event detected"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]
}
