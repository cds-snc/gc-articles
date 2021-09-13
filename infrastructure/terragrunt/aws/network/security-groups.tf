resource "aws_security_group" "wordpress_ecs" {
  # checkov:skip=CKV2_AWS_5: False positive, this is attached in the ecs module.
  name        = "wordpress_ecs"
  description = "WordPress ECS - ingress from load balancer, egress to database"
  vpc_id      = module.wordpress_vpc.vpc_id

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_security_group_rule" "wordpress_ecs_ingress_lb" {
  description              = "Security group rule for WordPress ingress from load balancer"
  type                     = "ingress"
  from_port                = 80
  to_port                  = 80
  protocol                 = "tcp"
  security_group_id        = aws_security_group.wordpress_ecs.id
  source_security_group_id = aws_security_group.wordpress_load_balancer.id
}

# This could be locked down to only the IP address ranges required for egress (e.g. List Manager IP range)
resource "aws_security_group_rule" "wordpress_ecs_egress_internet" {
  description       = "Security group rule for WordPress egress to internet"
  type              = "egress"
  from_port         = 443
  to_port           = 443
  protocol          = "tcp"
  security_group_id = aws_security_group.wordpress_ecs.id
  cidr_blocks       = ["0.0.0.0/0"]
}

resource "aws_security_group_rule" "wordpress_ecs_egress_efs" {
  count = var.enable_efs ? 1 : 0

  description              = "Security group rule for WordPress egress to EFS"
  type                     = "egress"
  from_port                = 2049
  to_port                  = 2049
  protocol                 = "tcp"
  security_group_id        = aws_security_group.wordpress_ecs.id
  source_security_group_id = aws_security_group.wordpress_efs.id
}

data "aws_subnet" "wordpress_private_subnet" {
  count = 3
  id    = tolist(module.wordpress_vpc.private_subnet_ids)[count.index]
}

resource "aws_security_group" "wordpress_load_balancer" {
  # checkov:skip=CKV2_AWS_5: False positive, attached in the "load-balancer" module.
  name        = "wordpress-load-balancer"
  description = "WordPress load balancer - ingress from internet, egress to ECS"
  vpc_id      = module.wordpress_vpc.vpc_id

  ingress {
    protocol    = "tcp"
    from_port   = 443
    to_port     = 443
    cidr_blocks = ["0.0.0.0/0"]
    description = "Requests from users"
  }

  dynamic "egress" {
    for_each = [for s in toset(data.aws_subnet.wordpress_private_subnet) : {
      cidr = s.cidr_block
      zone = s.availability_zone
    }]

    content {
      protocol    = "tcp"
      from_port   = 80
      to_port     = 80
      cidr_blocks = [egress.value.cidr]
      description = "Traffic to ECS cluster"
    }
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_security_group" "wordpress_efs" {
  # checkov:skip=CKV2_AWS_5: False positive, attached in the "ecs" module.
  name        = "wordpress_efs"
  description = "Wordpress EFS access"
  vpc_id      = module.wordpress_vpc.vpc_id

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_security_group_rule" "efs_ingress_wordpress_ecs" {
  count = var.enable_efs ? 1 : 0

  description              = "Security group rule for EFS ingress from WordPress"
  type                     = "ingress"
  from_port                = 2049
  to_port                  = 2049
  protocol                 = "tcp"
  security_group_id        = aws_security_group.wordpress_efs.id
  source_security_group_id = aws_security_group.wordpress_ecs.id
}

resource "aws_security_group" "vpc_endpoint" {
  name        = "vpc_endpoints"
  description = "PrivateLink VPC endpoints"
  vpc_id      = module.wordpress_vpc.vpc_id

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_security_group_rule" "vpc_endpoint_wordpress_ecs_ingress" {
  description              = "Security group rule WordPress ECS ingress to VPC endpoints"
  type                     = "ingress"
  from_port                = 443
  to_port                  = 443
  protocol                 = "tcp"
  security_group_id        = aws_security_group.vpc_endpoint.id
  source_security_group_id = aws_security_group.wordpress_ecs.id
}

resource "aws_security_group_rule" "vpc_endpoint_wordpress_ecs_egress" {
  description              = "Security group rule WordPress ECS egress to VPC endpoints"
  type                     = "egress"
  from_port                = 443
  to_port                  = 443
  protocol                 = "tcp"
  security_group_id        = aws_security_group.wordpress_ecs.id
  source_security_group_id = aws_security_group.vpc_endpoint.id
}

resource "aws_security_group_rule" "vpc_endpoint_sns_lambda_egress" {
  description              = "Security group rule for VPC endpoints egress to SNS Lambda"
  type                     = "egress"
  from_port                = 443
  to_port                  = 443
  protocol                 = "tcp"
  security_group_id        = aws_security_group.vpc_endpoint.id
  source_security_group_id = aws_security_group.sns_lambda.id
}

resource "aws_security_group_rule" "s3_gateway_wordpress_ecs_egress" {
  description       = "Security group rule for Wordpress ECS S3 egress through VPC endpoints"
  type              = "egress"
  from_port         = 443
  to_port           = 443
  protocol          = "tcp"
  security_group_id = aws_security_group.wordpress_ecs.id
  prefix_list_ids = [
    aws_vpc_endpoint.s3.prefix_list_id
  ]
}

resource "aws_security_group" "sns_lambda" {
  # checkov:skip=CKV2_AWS_5: False positive, attached in the "alarms" module.
  name        = "sns_lambda"
  description = "SNS Lambda - egress to internet, ingress to VPC endpoints"
  vpc_id      = module.wordpress_vpc.vpc_id

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_security_group_rule" "sns_lambda_egress" {
  description       = "Security group rule for SNS Lambda egress to internet"
  type              = "egress"
  from_port         = 443
  to_port           = 443
  protocol          = "tcp"
  security_group_id = aws_security_group.sns_lambda.id
  cidr_blocks       = ["0.0.0.0/0"]
}

resource "aws_security_group_rule" "sns_lambda_vpc_endpoint_ingress" {
  description              = "Security group rule for SNS Lambda ingress from VPC endpoints"
  type                     = "ingress"
  from_port                = 443
  to_port                  = 443
  protocol                 = "tcp"
  security_group_id        = aws_security_group.sns_lambda.id
  source_security_group_id = aws_security_group.vpc_endpoint.id
}
