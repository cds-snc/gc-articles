#
# CloudFront: 4xx response
#
resource "aws_cloudwatch_metric_alarm" "cloudfront_4xx_response" {
  provider = aws.us-east-1

  alarm_name          = "CloudFront4xxResponse"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "4xxErrorRate"
  namespace           = "AWS/CloudFront"
  period              = "300"
  statistic           = "Average"
  threshold           = var.cloudfront_4xx_maximum

  alarm_description = "Monitors for high CloudFront 4xx responses in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning_us_east.arn]
  ok_actions        = [aws_sns_topic.alert_warning_us_east.arn]

  dimensions = {
    DistributionId = var.cloudfront_distribution_id
    Region         = "Global"
  }
}
