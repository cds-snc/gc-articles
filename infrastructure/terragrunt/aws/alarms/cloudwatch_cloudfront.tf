#
# CloudFront: 4xx and 5xx responses
#
resource "aws_cloudwatch_metric_alarm" "cloudfront_5xx_response" {
  provider = aws.us-east-1

  alarm_name          = "CloudFront5xxResponse"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "5xxErrorRate"
  namespace           = "AWS/CloudFront"
  period              = "300"
  statistic           = "Average"
  threshold           = var.cloudfront_5xx_maximum

  alarm_description = "Monitors for high CloudFront 5xx responses in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning_us_east.arn]
  ok_actions        = [aws_sns_topic.alert_warning_us_east.arn]

  dimensions = {
    DistributionId = var.cloudfront_distribution_id
    Region         = "Global"
  }
}

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

#
# WAF: high percentage of blocked requests
# 
resource "aws_cloudwatch_metric_alarm" "cloudfront_waf_blocked_requests_percent" {
  provider = aws.us-east-1

  alarm_name          = "WAFBlockedRequestsPercent"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  threshold           = "50"

  alarm_description = "More than 50% of requests being blocked in 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning_us_east.arn]
  ok_actions        = [aws_sns_topic.alert_warning_us_east.arn]

  metric_query {
    id          = "blocked_request_percent"
    expression  = "100*blocked/(blocked+allowed)"
    label       = "WAF blocked requests percent"
    return_data = "true"
  }

  metric_query {
    id = "blocked"
    metric {
      metric_name = "BlockedRequests"
      namespace   = "AWS/WAFV2"
      period      = "300"
      stat        = "Sum"

      dimensions = {
        Rule   = "ALL"
        WebACL = var.cloudfront_waf_web_acl_name
      }
    }
  }

  metric_query {
    id = "allowed"
    metric {
      metric_name = "AllowedRequests"
      namespace   = "AWS/WAFV2"
      period      = "300"
      stat        = "Sum"

      dimensions = {
        Rule   = "ALL"
        WebACL = var.cloudfront_waf_web_acl_name
      }
    }
  }
}
