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
