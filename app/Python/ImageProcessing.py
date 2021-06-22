import sys
import cv2
import matplotlib.pyplot as plt
import numpy as np

def get_hsv_info(img_rgb):
  img_hsv = cv2.cvtColor(img_rgb, cv2.COLOR_RGB2HSV) # 色空間をHSVに変換
  h,s,v = cv2.split(img_hsv) # 各成分に分割

  def get_percentile_info(k, data):
    percentile = [10,50,90] # パーセントタイルを設定

    return_info = {}
    for i in percentile:
      value = np.percentile(np.array(data), i)
      s = k + "_" + str(i)
      return_info[s] = value

    return return_info
  
  return_info = {}

  # 色相
  info = get_percentile_info("h_per", h.ravel())
  return_info.update(info)

  # 彩度
  info = get_percentile_info("s_per", s.ravel())
  return_info.update(info)

  # 明度
  info = get_percentile_info("v_per", v.ravel())
  return_info.update(info)

  return return_info

def adjust_s(img_rgb, rate):
  img_hsv = cv2.cvtColor(img_rgb, cv2.COLOR_RGB2HSV) # 色空間をHSVに変換
  img_hsv[:,:,(1)] *= rate # 彩度を調節する
  img_rgb = cv2.cvtColor(img_hsv, cv2.COLOR_HSV2RGB)  # 色空間をRGBに変換

  return img_rgb

def adjust_v(img_rgb, rate):
  # 整数型で2次元配列を作成[256,1]
  lookup_table = np.zeros((256, 1), dtype = 'uint8')  
  for loop in range(256):  
    # γテーブルを作成  
    lookup_table[loop][0] = 255 * pow(float(loop)/255, 1.0/rate)  

  # lookup Tableを用いて配列を変換 
  img_rgb = cv2.LUT(img_rgb, lookup_table) 
  
  return img_rgb

args = sys.argv # コマンドライン引数を取得

importFileName = '/Applications/MAMP/htdocs/image_processing/public/images/' + args[1]

img = cv2.imread(importFileName) # 画像データを取得
img_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB) # BGRからRGBに変換

# 自動加工画像１
img_info = get_hsv_info(img_rgb) # 画像の情報を取得

rate = round(100/img_info['s_per_50'])
img_edit = adjust_s(img_rgb, rate) # 彩度を調節

rate = round(100/img_info['v_per_50'], 1)
img_edit = adjust_v(img_edit, rate) # 明度を調節

img_edit_info_1 = get_hsv_info(img_edit) # 画像の情報を取得

img_bgr = cv2.cvtColor(img_edit, cv2.COLOR_RGB2BGR) # RGBからBGRに変換
exportFileName = '/Applications/MAMP/htdocs/image_processing/public/images/a' + args[1]
cv2.imwrite(exportFileName, img_bgr) # 画像データを保存

# 自動加工画像２
rate = round(150/img_info['s_per_50'])
img_edit = adjust_s(img_rgb, rate) # 彩度を調節

rate = round(150/img_info['v_per_50'], 1)
img_edit = adjust_v(img_edit, rate) # 明度を調節

img_edit_info_2 = get_hsv_info(img_edit) # 画像の情報を取得

img_bgr = cv2.cvtColor(img_edit, cv2.COLOR_RGB2BGR) # RGBからBGRに変換
exportFileName = '/Applications/MAMP/htdocs/image_processing/public/images/b' + args[1]
cv2.imwrite(exportFileName, img_bgr) # 画像データを保存

print(str(args[1]) + '/' + str(round(img_edit_info_1['s_per_50']/img_info['s_per_50'], 1)) +  '/' + str(round(img_edit_info_1['v_per_50']/img_info['v_per_50'], 1)) +  '/' + str(round(img_edit_info_2['s_per_50']/img_info['s_per_50'], 1)) +  '/' + str(round(img_edit_info_2['v_per_50']/img_info['v_per_50'], 1)))