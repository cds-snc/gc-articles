resource "aws_lb" "wordpress" {
  name               = "wordpress"
  internal           = false
  load_balancer_type = "application"

  drop_invalid_header_fields = true
  enable_deletion_protection = true

  security_groups = [
    var.load_balancer_security_group_id
  ]
  subnets = var.public_subnet_ids

  access_logs {
    bucket  = var.cbs_satellite_bucket_name
    prefix  = "lb_logs"
    enabled = true
  }

  tags = {
    Name                  = "wordpress"
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_lb_target_group" "wordpress" {
  name_prefix          = "wp"
  port                 = 443
  protocol             = "HTTPS"
  target_type          = "ip"
  deregistration_delay = 30
  vpc_id               = var.vpc_id

  health_check {
    enabled             = true
    interval            = 10
    port                = 443
    protocol            = "HTTPS"
    path                = "/"
    matcher             = "200-399"
    timeout             = 5
    healthy_threshold   = 2
    unhealthy_threshold = 2
  }

  stickiness {
    type = "lb_cookie"
  }

  tags = {
    Name                  = "wordpress"
    (var.billing_tag_key) = var.billing_tag_value
  }

  lifecycle {
    create_before_destroy = true
    ignore_changes = [
      stickiness[0].cookie_name
    ]
  }
}

resource "aws_lb_listener" "wordpress" {
  # checkov:skip=CKV_AWS_103: false-positive, SSL policy is TLS1.2+
  load_balancer_arn = aws_lb.wordpress.arn
  port              = "443"
  protocol          = "HTTPS"
  ssl_policy        = "ELBSecurityPolicy-TLS13-1-2-FIPS-2023-04"
  certificate_arn   = aws_acm_certificate.wordpress.arn

  default_action {
    type             = "forward"
    target_group_arn = aws_lb_target_group.wordpress.arn
  }

  depends_on = [
    aws_acm_certificate.wordpress,
    aws_acm_certificate_validation.wordpress,
    aws_route53_record.wordpress_validation,
  ]
}

# Serve security.txt as a fixed response from the ALB
resource "aws_alb_listener_rule" "security_txt" {
  listener_arn = aws_lb_listener.wordpress.arn
  priority     = 100

  action {
    type = "fixed-response"

    fixed_response {
      content_type = "text/plain"
      message_body = var.security_txt_content
      status_code  = "200"
    }
  }

  condition {
    path_pattern {
      values = ["/.well-known/security.txt"]
    }
  }

  tags = {
    Name                  = "wordpress"
    (var.billing_tag_key) = var.billing_tag_value
  }
}
