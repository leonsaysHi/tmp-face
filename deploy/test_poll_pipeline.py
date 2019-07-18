from unittest import TestCase
import unittest
from unittest.mock import patch
from unittest import mock
import json
from poll_pipeline import PollPipeline
from datetime import timedelta, timezone, datetime

with open("fixtures/get_pipeline_state.json") as payload:
    pipeline_job_info = json.load(payload)


class TestReplace(TestCase):
    def setUp(self):
        """ setup """

    # @patch(
    #     "poll_pipeline.PollPipeline.get_pipeline_state", return_value=pipeline_job_info
    # )
    # @patch(
    #     "poll_pipeline.PollPipeline.get_nowish_time",
    #     return_value=datetime.now(timezone.utc) - timedelta(days=2),
    # )
    def test_can_get_pipeline(self):
        pipeline = PollPipeline()
        results = pipeline.poll()
