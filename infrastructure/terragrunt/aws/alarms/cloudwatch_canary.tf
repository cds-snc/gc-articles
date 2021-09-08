#
# Synthetic canary: health check
#
resource "aws_cloudwatch_metric_alarm" "wordpress_canary_healthcheck" {
  alarm_name          = "CanaryHealthCheck"
  comparison_operator = "LessThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "SuccessPercent"
  namespace           = "CloudWatchSynthetics"
  period              = "300"
  statistic           = "Minimum"
  threshold           = "100"
  treat_missing_data  = "notBreaching"

  alarm_description = "Monitors for failing synthetic canary health checks"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    CanaryName = aws_synthetics_canary.wordpress.name
  }
}
