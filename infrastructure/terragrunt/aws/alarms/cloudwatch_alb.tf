#
# ALB: target group health and response times
# 
resource "aws_cloudwatch_metric_alarm" "alb_5xx_response" {
  alarm_name          = "ALB5xxResponse"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "HTTPCode_Target_5XX_Count"
  namespace           = "AWS/ApplicationELB"
  period              = "300"
  statistic           = "Sum"
  threshold           = var.alb_5xx_maximum
  treat_missing_data  = "notBreaching"

  alarm_description = "Sum of 5xx response from the ALB in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    "LoadBalancer" = var.alb_arn_suffix
  }
}

resource "aws_cloudwatch_metric_alarm" "alb_target_4xx_response" {
  alarm_name          = "ALBTargetGroup4xxResponse"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "HTTPCode_Target_4XX_Count"
  namespace           = "AWS/ApplicationELB"
  period              = "300"
  statistic           = "Sum"
  threshold           = var.alb_target_4xx_maximum
  treat_missing_data  = "notBreaching"

  alarm_description = "Sum of 4xx response from the ALB target group in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    "TargetGroup"  = var.alb_target_group_arn_suffix
    "LoadBalancer" = var.alb_arn_suffix
  }
}

resource "aws_cloudwatch_metric_alarm" "alb_target_5xx_response" {
  alarm_name          = "ALBTargetGroup5xxResponse"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "HTTPCode_Target_5XX_Count"
  namespace           = "AWS/ApplicationELB"
  period              = "300"
  statistic           = "Sum"
  threshold           = var.alb_target_5xx_maximum
  treat_missing_data  = "notBreaching"

  alarm_description = "Sum of 5xx response from the ALB target group in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    "TargetGroup"  = var.alb_target_group_arn_suffix
    "LoadBalancer" = var.alb_arn_suffix
  }
}

resource "aws_cloudwatch_metric_alarm" "alb_target_response_time_average" {
  alarm_name          = "ALBTargetGroupResponseTimeAverage"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "TargetResponseTime"
  namespace           = "AWS/ApplicationELB"
  period              = "300"
  statistic           = "Average"
  threshold           = var.alb_target_response_time_average_maximum
  treat_missing_data  = "notBreaching"

  alarm_description = "Average response time (seconds) for the ALB target group to receive a response in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    "TargetGroup"  = var.alb_target_group_arn_suffix
    "LoadBalancer" = var.alb_arn_suffix
  }
}

resource "aws_cloudwatch_metric_alarm" "alb_target_unhealthy_host" {
  alarm_name          = "ALBTargetUnhealthyHost"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "UnHealthyHostCount"
  namespace           = "AWS/ApplicationELB"
  period              = "300"
  statistic           = "Maximum"
  threshold           = "0"
  treat_missing_data  = "notBreaching"

  alarm_description = "Unhealthy ALB target group hosts in a 5 minute period"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]
  ok_actions        = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    "TargetGroup"  = var.alb_target_group_arn_suffix
    "LoadBalancer" = var.alb_arn_suffix
  }
}
