<?php
class XMLHelper{
	public static function readXmlFile($path){
		$xml = new DOMDocument();
		$xml->load($path);
		return $xml->saveXML();
	}

	public static function getXmlFromFile($path){
		$xml = new DOMDocument();
		$xml->load($path);
		return $xml;
	}

	public static function demo(){
		$xml = XMLHelper::getXmlFromFile('../../demo/autobuild/resource/CustomerRegisterInformation.plist');
		//edit the data
		$items = $xml->documentElement;

		//get the list of item which contain the setting
		$settingItems = null;
		foreach($items->childNodes as $item) { 
			if($item->nodeName == 'dict'){
				$settingItems = $item->childNodes;
				break;
			}
		}

		if($settingItems){
			//Edit the data

			//edit the customer id
			$customerIdKey = $settingItems->item(1);
			$customerIdValue = $settingItems->item(3);
			if($customerIdKey && $customerIdValue && $customerIdKey->nodeValue == 'CustomerID'){
				$customerIdValue->nodeValue = '001';
			}

			//edit the Style
			$styleKey = $settingItems->item(5);
			$styleValue = $settingItems->item(7);
			if($styleKey && $styleValue && $styleKey->nodeValue == 'style'){
				$styleValue->nodeValue = 1;
			}

			//edit the ThemeColor
			$themeColorKey = $settingItems->item(9);
			$themeColorValue = $settingItems->item(11);
			if($themeColorKey && $themeColorValue && $themeColorKey->nodeValue == 'ThemeColor'){
				$themeColorValue->nodeValue = '0x222222';
			}

			//edit the ColorTitle
			$themeColorKey = $settingItems->item(13);
			$themeColorValue = $settingItems->item(15);
			if($themeColorKey && $themeColorValue && $themeColorKey->nodeValue == 'TitleColor'){
				$themeColorValue->nodeValue = '0x121212';
			}

		}

		$xml->save('../../demo/autobuild/resource/CustomerRegisterInformation.plist');

		//change the info-plist
		$xml = XMLHelper::getXmlFromFile('../../demo/autobuild/resource/CompendiumHQ-Info.plist');

		//edit the data
		$items = $xml->documentElement;

		//get the list of item which contain the setting
		$settingItems = null;
		foreach($items->childNodes as $item) { 
			if($item->nodeName == 'dict'){
				$settingItems = $item;
				break;
			}
		}

		//change the name
		$nameIndex = -1;
		foreach($settingItems->childNodes as $settingItem) { 
			if($settingItem->nodeValue == 'CFBundleName'){
				//jump two time because of xcode setting that the next will be empty
				$nextSibling = $settingItem->nextSibling->nextSibling;
				if($nextSibling){
					
					if($nextSibling->nodeName == 'string'){
						$nextSibling->nodeValue = 'MyTestAppName';
					}
				}
			}
		}

		$xml->save('../../demo/autobuild/resource/CompendiumHQ-Info.plist');
	}
}
?>