#
# SNS: topics & subscriptions
#
resource "aws_sns_topic" "alert_warning" {
  name = "alert-warning"

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_sns_topic" "alert_warning_us_east" {
  provider = aws.us-east-1

  name = "alert-warning"

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_sns_topic_subscription" "alert_warning" {
  topic_arn = aws_sns_topic.alert_warning.arn
  protocol  = "lambda"
  endpoint  = aws_lambda_function.notify_slack.arn
}

resource "aws_sns_topic_subscription" "alert_warning_us_east" {
  provider = aws.us-east-1

  topic_arn = aws_sns_topic.alert_warning_us_east.arn
  protocol  = "lambda"
  endpoint  = aws_lambda_function.notify_slack.arn
}
