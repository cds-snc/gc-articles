#
# EFS: limited burst performance and high file I/O
# 
resource "aws_cloudwatch_metric_alarm" "burst_credit_balance" {
  count = var.enable_efs ? 1 : 0

  alarm_name          = "EFSBurstCreditBalance"
  comparison_operator = "LessThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "BurstCreditBalance"
  namespace           = "AWS/EFS"
  period              = "300"
  statistic           = "Average"
  threshold           = var.efs_burst_credit_balance

  alarm_description = "Average burst credit balance over 5 minute period - low credit balance leads to degraded performance"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    FileSystemId = var.efs_id
  }
}

resource "aws_cloudwatch_metric_alarm" "percent_io_limit" {
  count = var.enable_efs ? 1 : 0

  alarm_name          = "EFSPercentIOLimit"
  comparison_operator = "GreaterThanThreshold"
  evaluation_periods  = "1"
  metric_name         = "PercentIOLimit"
  namespace           = "AWS/EFS"
  period              = "300"
  statistic           = "Maximum"
  threshold           = var.efs_percent_io_limit

  alarm_description = "File I/O limit over 5 minute period - sustained high I/O leads to degraded performance"
  alarm_actions     = [aws_sns_topic.alert_warning.arn]

  dimensions = {
    FileSystemId = var.efs_id
  }
}
