#
# DDoS: ALB, CloudFront and Route53
#
resource "aws_cloudwatch_metric_alarm" "alb_ddos" {
  alarm_name          = "ALBDDoS"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "DDoSDetected"
  namespace           = "AWS/DDoSProtection"
  period              = "60"
  statistic           = "Sum"
  threshold           = "0"

  alarm_description = "DDoS detection for ALB"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    ResourceArn = var.alb_arn
  }
}

resource "aws_cloudwatch_metric_alarm" "cloudfront_ddos" {
  provider = aws.us-east-1

  alarm_name          = "CloudFrontDDoS"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "DDoSDetected"
  namespace           = "AWS/DDoSProtection"
  period              = "60"
  statistic           = "Sum"
  threshold           = "0"

  alarm_description = "DDoS detection for CloudFront"
  alarm_actions     = [aws_sns_topic.alert_warning_us_east.arn]

  dimensions = {
    ResourceArn = var.cloudfront_arn
  }
}

resource "aws_cloudwatch_metric_alarm" "route53_ddos" {
  provider = aws.us-east-1

  alarm_name          = "Route53DDoS"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "DDoSDetected"
  namespace           = "AWS/DDoSProtection"
  period              = "60"
  statistic           = "Sum"
  threshold           = "0"

  alarm_description = "DDoS detection for Route53"
  alarm_actions     = [aws_sns_topic.alert_warning_us_east.arn]

  dimensions = {
    ResourceArn = "arn:aws:route53:::hostedzone/${var.hosted_zone_id}"
  }
}
