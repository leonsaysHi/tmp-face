import json
import os
import boto3
from datetime import timedelta, timezone, datetime
import time
import os

aws_region = "eu-west-1"
if "AWS_DEFAULT_REGION" in os.environ:
    aws_region = os.environ.get("AWS_DEFAULT_REGION")

client = boto3.client("codepipeline", aws_region)


class PollPipeline:
    def __init__(self):
        """ poll the pipeline to let travis know when done """
        self.nowish = self.get_nowish_time()
        self.done_states = ["Failed", "Succeeded"]
        self.pipeline_name = False
        if "PipelineName" in os.environ:
            self.pipeline_name = os.environ.get("PipelineName")

    def get_pipeline_state(self):
        if self.pipeline_name == False:
            raise Exception("Missing PipelineName Environment Variable")
        return client.get_pipeline_state(name=self.pipeline_name)

    def get_nowish_time(self):
        return datetime.now(timezone.utc) - timedelta(minutes=2)

    def do_we_need_to_fail_job(self, stage):
        for actionState in stage["actionStates"]:
            if (
                "latestExecution" in actionState
                and self.nowish < actionState["latestExecution"]["lastStatusChange"]
            ):
                if actionState["latestExecution"]["status"] == "Failed":
                    raise Exception("Stage %s has failed" % stage["stageName"])

    def poll(self):
        results = self.get_pipeline_state()
        stages = results["stageStates"]
        for stage in stages:
            """ see if stage is newer than now """
            """ if so and failed we just need to stop """
            self.do_we_need_to_fail_job(stage)

            if stage["stageName"] == "Deploy":
                for actionState in stage["actionStates"]:
                    if "latestExecution" in actionState:
                        latest_execution_date = actionState["latestExecution"][
                            "lastStatusChange"
                        ]

                        if self.nowish < latest_execution_date:
                            """ if it is then we are ready to poll """
                            """   get the status type and see if it is done """
                            """   else try this all over again """
                            print(
                                "Now is Less than Lastest Execution Date so need to enter loop to check on Deploy Status"
                            )
                            self.nowish = latest_execution_date - timedelta(minutes=2)
                            print("Checking Status of build")
                            status = actionState["latestExecution"]["status"]
                            print("status", status)
                            if status == "Failed":
                                raise Exception("The Deploy status is set to Failed")
                            elif status == "Succeeded":
                                print("Success with Deploy done looping over status")
                                return True
                            else:
                                print(
                                    "Deploy In Progress will check again in 5 seconds"
                                )
                                self.loop()
                        else:
                            """ well seems job has not started so going to try again """
                            print(
                                "Deploy not started checking again shortly after Build Phase is done"
                            )
                            self.loop()

    def loop(self):
        print("Pausing 5 seconds")
        time.sleep(5)
        self.poll()

        # print(results)


if __name__ == "__main__":
    pipeline = PollPipeline()
    pipeline.poll()
