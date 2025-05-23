resource "aws_efs_file_system" "wordpress" {
  # checkov:skip=CKV2_AWS_18: Automated EFS backups are enabled using aws_efs_backup_policy.wordpress resource 
  count = var.enable_efs ? 1 : 0

  encrypted = true
  tags = {
    Name                  = "${var.cluster_name}-efs"
    (var.billing_tag_key) = var.billing_tag_value
  }
}

resource "aws_efs_file_system_policy" "wordpress" {
  count = var.enable_efs ? 1 : 0

  file_system_id = aws_efs_file_system.wordpress[0].id
  policy         = data.aws_iam_policy_document.wordpress_efs_policy[0].json
}

data "aws_iam_policy_document" "wordpress_efs_policy" {
  count = var.enable_efs ? 1 : 0

  statement {
    sid    = "AllowAccessThroughAccessPoint"
    effect = "Allow"
    actions = [
      "elasticfilesystem:ClientMount",
      "elasticfilesystem:ClientWrite",
    ]
    resources = [aws_efs_file_system.wordpress[0].arn]
    principals {
      type        = "AWS"
      identifiers = ["*"]
    }
    condition {
      test     = "StringEquals"
      variable = "elasticfilesystem:AccessPointArn"
      values = [
        aws_efs_access_point.wordpress[0].arn
      ]
    }
  }

  statement {
    sid       = "DenyNonSecureTransport"
    effect    = "Deny"
    actions   = ["*"]
    resources = [aws_efs_file_system.wordpress[0].arn]
    principals {
      type        = "AWS"
      identifiers = ["*"]
    }
    condition {
      test     = "Bool"
      variable = "aws:SecureTransport"
      values = [
        "false"
      ]
    }
  }
}

resource "aws_efs_backup_policy" "wordpress" {
  count = var.enable_efs ? 1 : 0

  file_system_id = aws_efs_file_system.wordpress[0].id

  backup_policy {
    status = "ENABLED"
  }
}

resource "aws_efs_mount_target" "wordpress" {
  count = var.enable_efs ? 3 : 0

  file_system_id = aws_efs_file_system.wordpress[0].id
  subnet_id      = tolist(var.private_subnet_ids)[count.index]
  security_groups = [
    var.efs_security_group_id
  ]
}

resource "aws_efs_access_point" "wordpress" {
  count = var.enable_efs ? 1 : 0

  file_system_id = aws_efs_file_system.wordpress[0].id
  posix_user {
    gid = 33
    uid = 33
  }
  root_directory {
    path = "/var/www/html/wp-content"
    creation_info {
      owner_gid   = 33
      owner_uid   = 33
      permissions = 775
    }
  }
  tags = {
    Name                  = "${var.cluster_name}-access"
    (var.billing_tag_key) = var.billing_tag_value
  }
}
