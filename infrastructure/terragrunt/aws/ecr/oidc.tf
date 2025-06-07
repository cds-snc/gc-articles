locals {
  ecr_tag_release = "gc-articles-ecr-tag-release"
}

module "ecr_tag_release" {
  source            = "github.com/cds-snc/terraform-modules//gh_oidc_role?ref=v10.4.7"
  billing_tag_value = var.billing_tag_value
  roles = [
    {
      name      = local.ecr_tag_release
      repo_name = "gc-articles"
      claim     = "ref:refs/tags/v*"
    }
  ]
}

resource "aws_iam_role_policy_attachment" "ecr_tag_release" {
  role       = local.ecr_tag_release
  policy_arn = aws_iam_policy.ecr_push.arn
  depends_on = [
    module.ecr_tag_release
  ]
}

resource "aws_iam_policy" "ecr_push" {
  name   = "wordpress-ecr-push"
  path   = "/"
  policy = data.aws_iam_policy_document.ecr_push.json
}

data "aws_iam_policy_document" "ecr_push" {
  statement {
    effect = "Allow"
    actions = [
      "ecr:BatchCheckLayerAvailability",
      "ecr:BatchGetImage",
      "ecr:CompleteLayerUpload",
      "ecr:DescribeImages",
      "ecr:DescribeRepositories",
      "ecr:GetDownloadUrlForLayer",
      "ecr:GetRepositoryPolicy",
      "ecr:InitiateLayerUpload",
      "ecr:ListImages",
      "ecr:PutImage",
      "ecr:UploadLayerPart"
    ]
    resources = [
      aws_ecr_repository.wordpress.arn
    ]
  }

  statement {
    effect = "Allow"
    actions = [
      "ecr:GetAuthorizationToken"
    ]
    resources = ["*"]
  }
}
