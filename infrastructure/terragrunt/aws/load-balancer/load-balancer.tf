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
    bucket  = module.wordpress_lb_logs.s3_bucket_id
    prefix  = "wordpress"
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
    port                = 80
    protocol            = "HTTP"
    path                = "/index.html"
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
  load_balancer_arn = aws_lb.wordpress.arn
  port              = "443"
  protocol          = "HTTPS"
  ssl_policy        = "ELBSecurityPolicy-FS-1-2-Res-2019-08"
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
