#!/bin/bash

curl https://pfizerbt--Haigang.cs62.my.salesforce.com/services/data/v44.0/sobjects/ContentVersion -H "Authorization: Bearer TOKENS_DO_NOT_BELONG_IN_GITHUB" -H "Content-Type: multipart/form-data; boundary=\"boundary_string\"" --data-binary @NewContentVersion.txt
