# Press Loft Affiliates - Magento 1.8 / 1.9 Extension

This project contains source code for the Magento 1 extension.  Please note that this extension assumes that you have a standard installation of Magento 1.8 or 1.9.

## Installation guide
1. Back up your store and database.
2. Download the extension files.
3. Copy the extension files to your Magento installation whilst maintaining the file structure of the extension.
4. If you are using a custom theme, it is recommended to copy the design files to your current theme's folders.
5. Flush the Magento cache and cache storage.
6. Ensure the new module (called `PressLoft_Affiliate`) is enabled.
7. Navigate to `System > Configuration` and you will find a new link in the left-hand menu under `PRESSLOFT`

## Useful links
 - [Cloud formation](https://eu-west-2.console.aws.amazon.com/cloudformation/home?region=eu-west-2#/stacks/stackinfo?filteringStatus=active&filteringText=&viewNested=true&hideStacks=false&stackId=arn%3Aaws%3Acloudformation%3Aeu-west-2%3A088543416560%3Astack%2Fpressloft-sam-stack%2Fa7572e80-5d9d-11ec-80e4-0a0ec778834e)
 - [Lambda functions](https://eu-west-2.console.aws.amazon.com/lambda/home?region=eu-west-2#/functions?f0=false&f1=true&k0=tag-indicator__environment&k1=name&n0=false&n1=false&op=and&v0=production&v1=pressloft-sam-stack)
 - [Image processing step function](https://eu-west-2.console.aws.amazon.com/states/home?region=eu-west-2#/statemachines/view/arn:aws:states:eu-west-2:088543416560:stateMachine:ImageProcessingStateMachine-ApFQPDJ42aMn)
 - [Code pipeline](https://eu-west-2.console.aws.amazon.com/codesuite/codepipeline/pipelines/PressLoftSAMPipeline/view?region=eu-west-2)

## Setting up
First you will need to clone a copy of this repo into your dev directory so that it lives in: /var/www/dev*.pressloft.com/sam-lambda-stack.

The development server is set up to use this [IAM user](https://console.aws.amazon.com/iam/home#/users/dev-server). When creating a new function, make sure the role it uses has the correct permissions for the services you wish the function to access. When invoking functions on the dev server, it uses the *__dev-server__* user which has full access to any AWS service, however once deployed the function will use the role you've assigned it in the template.yaml file, which may not have the correct permissions.

## Testing your changes
In order to test the changes to your code, you must first build your function using the following command:
 > `sam build <YOUR-FUNCTION-NAME> --use-container`

The *--use-container* flag means the function will be mounted to a docker container and exectuted in the runtime enmvironment that matches the environment lambda runs in.

If you wished to build all your functions in one command, you can run the following command:
 > `sam build --use-container`

Once the function has been built, you can then invoke your function using the following command:
 > `sam local invoke <YOUR-FUNCTION-NAME> -e events/<EVENT-FILE>.json`

The <EVENTS-FILE> is the testing data you wish to pass to the function when it's invoked.
