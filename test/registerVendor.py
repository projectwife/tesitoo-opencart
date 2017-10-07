# -*- coding: utf-8 -*-
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
from selenium.common.exceptions import NoSuchElementException
from selenium.common.exceptions import NoAlertPresentException
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities
import unittest, time, re, sys, argparse

class RegisterVendor(unittest.TestCase):
    base_url = "http://localhost/opencart/"

    def setUp(self):
        #self.driver = webdriver.Firefox()
        #firefox_capabilities = DesiredCapabilities.CHROME
        #firefox_capabilities['marionette'] = False
        self.driver = webdriver.Remote(
            command_executor='http://127.0.0.1:4444/wd/hub',
            desired_capabilities=webdriver.DesiredCapabilities.FIREFOX)
        self.driver.implicitly_wait(30)
        self.verificationErrors = []
        self.accept_next_alert = True
    
    def test_register_vendor(self):
        driver = self.driver
        driver.get(self.base_url + "index.php?route=common/home")
        driver.find_element_by_xpath("//a[@title='Register']").click()
        driver.find_element_by_link_text("Register as a Farmer").click()
        driver.find_element_by_id("input-username").clear()
        driver.find_element_by_id("input-username").send_keys("selenium1")
        driver.find_element_by_id("input-firstname").clear()
        driver.find_element_by_id("input-firstname").send_keys("selenium1")
        driver.find_element_by_id("input-lastname").clear()
        driver.find_element_by_id("input-lastname").send_keys("selenium1")
        driver.find_element_by_id("input-email").clear()
        driver.find_element_by_id("input-email").send_keys("selenium1@example.com")
        #driver.find_element_by_id("input-paypal").clear()
        #driver.find_element_by_id("input-paypal").send_keys("selenium1@example.com")
        driver.find_element_by_id("input-telephone").clear()
        driver.find_element_by_id("input-telephone").send_keys("1234567890")
        driver.find_element_by_id("input-company").clear()
        driver.find_element_by_id("input-company").send_keys("selenium1")
        #driver.find_element_by_id("input-address-1").clear()
        #driver.find_element_by_id("input-address-1").send_keys("addr1")
        driver.find_element_by_id("input-city").clear()
        driver.find_element_by_id("input-city").send_keys("city")
        #driver.find_element_by_id("input-postcode").clear()
        #driver.find_element_by_id("input-postcode").send_keys("p1 1p")
        Select(driver.find_element_by_id("input-country")).select_by_visible_text("Gambia")
        # ERROR: Caught exception [Error: locator strategy either id or name must be specified explicitly.]
        Select(driver.find_element_by_id("input-zone")).select_by_visible_text("Banjul")
        driver.find_element_by_id("input-password").clear()
        driver.find_element_by_id("input-password").send_keys("password")
        driver.find_element_by_id("input-confirm").clear()
        driver.find_element_by_id("input-confirm").send_keys("password")
        driver.find_element_by_xpath("//input[@name='agree']").click()
        driver.save_screenshot('test/circleci_artifacts/beforePrimaryButtonClick.png')
        #with open('test/circleci_artifacts/beforeFinalContinue.html', 'wb') as file_:
        #    file_.write(driver.page_source.encode('utf-8'))
        driver.find_element_by_css_selector("input.btn.btn-primary").click()
        assert "alert-danger" not in driver.page_source
        driver.find_element_by_css_selector("a.btn.btn-primary").click()
    
    def is_element_present(self, how, what):
        try: self.driver.find_element(by=how, value=what)
        except NoSuchElementException as e: return False
        return True
    
    def is_alert_present(self):
        try: self.driver.switch_to_alert()
        except NoAlertPresentException as e: return False
        return True
    
    def close_alert_and_get_its_text(self):
        try:
            alert = self.driver.switch_to_alert()
            alert_text = alert.text
            if self.accept_next_alert:
                alert.accept()
            else:
                alert.dismiss()
            return alert_text
        finally: self.accept_next_alert = True
    
    def tearDown(self):
        self.driver.quit()
        self.assertEqual([], self.verificationErrors)

if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument('--url', default='http://localhost/opencart/')
    parser.add_argument('unittest_args', nargs='*')

    args = parser.parse_args()
    RegisterVendor.base_url = args.url

    unit_argv = [sys.argv[0]] + args.unittest_args;
    unittest.main(argv=unit_argv)

